#Resource based policy to grant API gateway access to execute the lambda function
#{
#"Version": "2012-10-17",
#"Id": "default",
#"Statement": [
#{
#"Sid": "lambda-270c0307-97cd-49ef-a737-f5909e78d103",
#"Effect": "Allow",
#"Principal": {
#"Service": "apigateway.amazonaws.com"
#},
#"Action": "lambda:InvokeFunction",
#"Resource": "arn:aws:lambda:us-east-1:151967713887:function:lambda_handler",
#"Condition": {
#"ArnLike": {
#"AWS:SourceArn": "arn:aws:execute-api:us-east-1:151967713887:mfdgb0h6z8/*/*/lambda_handler"
#}
#}
#},
#{
#"Sid": "2f85f34d-ace8-5a5f-816e-69c4e2ac5885",
#"Effect": "Allow",
#"Principal": {
#"Service": "apigateway.amazonaws.com"
#},
#"Action": "lambda:InvokeFunction",
#"Resource": "arn:aws:lambda:us-east-1:151967713887:function:lambda_handler",
#"Condition": {
#"ArnLike": {
#"AWS:SourceArn": "arn:aws:execute-api:us-east-1:151967713887:mfdgb0h6z8/*/*/api/v1/visitor_count"
#}
#}
#}
#]
#}
data "archive_file" "lambda_visit" {
  type = "zip"

  source_file  = "${path.module}/upsert_visitor_count.py"
  output_path = "${path.module}/upsert_visitor_count.zip"
}

resource "aws_s3_bucket_object" "file_upload" {
  bucket = var.terraform_bucket_name
  key    = "${var.environment}/lambda/upsert_visitor_count.zip"
  source =  data.archive_file.lambda_visit.output_path
}

resource "aws_lambda_function" "dynamodb_visitor_count" {
  function_name = "get_visitor_count"
  description   = "get visitor count from dynamoDB"
  s3_bucket   = var.terraform_bucket_name
  s3_key      = aws_s3_bucket_object.file_upload.key
  runtime          = "python3.9"
  role             = aws_iam_role.lambda_exec.arn
  source_code_hash = base64sha256(data.archive_file.lambda_visit.output_path)
  handler          = "upsert_visitor_count.get_visitor_count"
}

resource "aws_cloudwatch_log_group" "lambda_log" {
  name = "/aws/lambda/${aws_lambda_function.dynamodb_visitor_count.function_name}"
  retention_in_days = 30
}

resource "aws_iam_role" "lambda_exec" {
  name = "lambda_to_dynamodb_role"
  assume_role_policy = jsonencode({
    Version = "2012-10-17"
    Statement = [{
      Action = "sts:AssumeRole"
      Effect = "Allow"
      Sid    = ""
      Principal = {
        Service = "lambda.amazonaws.com"
      }
    }
    ]
  })
}

resource "aws_iam_role_policy" "dynamodb_access" {
  name = "lambda_dynamodb_access"
  role   = aws_iam_role.lambda_exec.id
  policy = jsonencode({
    "Version": "2012-10-17",
    "Statement": [
      {
        "Effect": "Allow",
        "Action": [
          "dynamodb:DeleteItem",
          "dynamodb:GetItem",
          "dynamodb:PutItem",
          "dynamodb:Scan",
          "dynamodb:UpdateItem"
        ],
        "Resource": var.dynamodb_arn
      }
    ]
  })
}

resource "aws_iam_role_policy_attachment" "lambda_policy" {
  role       = aws_iam_role.lambda_exec.name
  policy_arn = "arn:aws:iam::aws:policy/service-role/AWSLambdaBasicExecutionRole"
}