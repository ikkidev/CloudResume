variable "domain_name" {
  type = string
  description = "The domain name for the website"
}

variable "common_tags" {
  type = map(string)
  description = "The common tag to apply to all components"
}

variable "bucket_name" {
  type = string
  description = "The globally unique name for S3 bucket.Typically set to domain_name without the www prefix"
}

variable "aws_profile" {
  type = string
  description = "The AWS profile to use for deploying AWS resources"
}

variable "terraform_bucket_name" {
  type = string
  description = "The S3 bucket name that holds terraform config"
}