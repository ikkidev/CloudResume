module "s3" {
  source = "./modules/s3"
  common_tags = var.common_tags
  bucket_name = var.bucket_name
  domain_name = var.domain_name
  aws_profile = var.aws_profile
}

module "dynamodb" {
  source = "./modules/dynamodb"
  common_tags = var.common_tags
}

module "lambda" {
  source = "./modules/lambda"
  terraform_bucket_name = var.terraform_bucket_name
  dynamodb_arn = module.dynamodb.table_arn
  environment = var.common_tags["Environment"]
}

module "apigateway" {
  source = "./modules/apigateway"
  lambda_function_name = module.lambda.function_name
  lambda_invoke_arn    = module.lambda.invoke_arn
}

module "acm" {
  source = "./modules/acm"
  common_tags = var.common_tags
  domain_name = var.domain_name
  aws_profile = var.aws_profile
  main_zone_id = module.route53.main_zone_id
}

module "cloudfront" {
  source = "./modules/cloudfront"
  common_tags = var.common_tags
  bucket_name = var.bucket_name
  domain_name = var.domain_name
  www_bucket_website_endpoint = module.s3.www_bucket_website_endpoint
  root_bucket_website_endpoint = module.s3.root_bucket_website_endpoint
  cert_validation_certificate_arn = module.acm.cert_validation_certificate_arn
}

module "route53" {
  source = "./modules/route53"
  common_tags = var.common_tags
  domain_name = var.domain_name
  root_s3_distribution_domain_name = module.cloudfront.root_s3_distribution_domain_name
  root_s3_distribution_hosted_zone_id = module.cloudfront.root_s3_distribution_hosted_zone_id
  www_s3_distribution_domain_name = module.cloudfront.www_s3_distribution_domain_name
  www_s3_distribution_hosted_zone_id = module.cloudfront.www_s3_distribution_hosted_zone_id
}

