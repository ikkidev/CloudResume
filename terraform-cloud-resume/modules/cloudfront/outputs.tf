output "root_s3_distribution_domain_name" {
  description = "The domain name of the corresponding root s3 distribution"
  value = aws_cloudfront_distribution.root_s3_distribution.domain_name
}

output "root_s3_distribution_hosted_zone_id" {
  description = "Route53 zone ID of root s3 distribution that can be used to route an Alias Resource Record Set to"
  value = aws_cloudfront_distribution.root_s3_distribution.hosted_zone_id
}

output "www_s3_distribution_domain_name" {
  description = "The domain name of the corresponding website s3 distribution"
  value = aws_cloudfront_distribution.www_s3_distribution.domain_name
}

output "www_s3_distribution_hosted_zone_id" {
  description = "Route53 zone ID of www s3 distribution that can be used to route an Alias Resource Record Set to"
  value = aws_cloudfront_distribution.www_s3_distribution.hosted_zone_id
}