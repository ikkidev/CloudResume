output "function_name" {
  description = "Name of the Lambda function."

  value = aws_lambda_function.dynamodb_visitor_count.function_name
}