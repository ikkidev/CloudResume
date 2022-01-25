provider "aws" {
  alias   = "s3"
  region  = "us-east-1"
  profile = var.aws_profile
}