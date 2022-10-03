variable "lambda_invoke_arn" {
  description = "The arn to use for invoking the Lambda function from API gateway"
  type = string
}

variable "lambda_function_name" {
  description = "Name of the Lambda function"
  type = string
}