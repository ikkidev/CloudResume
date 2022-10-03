output "function_name" {
  description = "Name of the Lambda function."
  value = aws_lambda_function.dynamodb_visitor_count.function_name
}

output "invoke_arn" {
  description = "The arn to use for invoking the Lambda function from API gateway"
  value = aws_lambda_function.dynamodb_visitor_count.invoke_arn
}