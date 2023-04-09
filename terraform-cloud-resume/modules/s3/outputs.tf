output "www_bucket_website_endpoint" {
  description = "Tne website endpoint of the website www bucket"
  value = aws_s3_bucket_website_configuration.www_bucket.website_endpoint
}
output "root_bucket_website_endpoint" {
  description =  "Tne website endpoint of the website root bucket for redirects"
  value = aws_s3_bucket_website_configuration.root_bucket.website_endpoint
}