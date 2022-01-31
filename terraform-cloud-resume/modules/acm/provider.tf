//ACM certificate with Amazon Cloudfront is only supported on us-east-1
//https://docs.aws.amazon.com/acm/latest/userguide/acm-regions.html
provider "aws" {
  alias = "cloudfront_provider"
  region = "us-east-1"
  profile = var.aws_profile
}

