output "lambda_function_name" {
  description = "Name of the Lambda function"
  value = module.lambda.function_name
}

output "lambda_invoke_arn" {
  description = "The arn to use for invoking the Lambda function from API gateway"
  value = module.lambda.invoke_arn
}

output "table_arn" {
  description = "ARN of the dynamoDB table"
  value = module.dynamodb.table_arn
}

output "apigateway_base_url" {
  description = "The base url of API gateway to invoke the lambda function"
  value = module.apigateway.base_url
}

output "www_bucket_website_endpoint" {
  description = "Tne website endpoint of the website www bucket"
  value = module.s3.www_bucket_website_endpoint
}
output "root_bucket_website_endpoint" {
  description =  "Tne website endpoint of the website root bucket for redirects"
  value = module.s3.root_bucket_website_endpoint
}
