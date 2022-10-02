variable "terraform_bucket_name" {
  type = string
  description = "The S3 bucket name that holds terraform config"
}

variable "dynamodb_arn" {
  type = string
  description = "The arn of the dynamoDB to access"
}

variable "environment" {
  type = string
  description = "The environment to deploy the lambda to"
}