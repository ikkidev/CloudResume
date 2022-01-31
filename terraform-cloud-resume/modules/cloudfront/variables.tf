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

variable "www_bucket_website_endpoint" {
  type = string
  description =  "Tne website endpoint of the website www bucket"
}

variable "root_bucket_website_endpoint" {
  type = string
  description =  "Tne website endpoint of the website root bucket for redirects"
}

variable "cert_validation_certificate_arn"{
  type = string
  description = "The ARN of the certificate that is being validated."
}