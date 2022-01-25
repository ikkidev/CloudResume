module "s3" {
  source = "./modules/s3"
  common_tags = var.common_tags
  bucket_name = var.bucket_name
  domain_name = var.domain_name
  aws_profile = var.aws_profile
}