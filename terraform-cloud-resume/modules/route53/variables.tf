variable "root_s3_distribution_domain_name" {
  type = string
  description = "The domain name of the corresponding root s3 distributrion"
}

variable "root_s3_distribution_hosted_zone_id" {
  type = string
  description = "Route53 zone ID of root s3 distribution that can be used to route an Alias Resource Record Set to"
}

variable "www_s3_distribution_domain_name" {
  type = string
  description = "The domain name of the corresponding website s3 distribution"

}

variable "www_s3_distribution_hosted_zone_id" {
  type = string
  description = "Route53 zone ID of www s3 distribution that can be used to route an Alias Resource Record Set to"
}

variable "domain_name" {
  type = string
  description = "The domain name for the website"
}

variable "common_tags" {
  type = map(string)
  description = "The common tag to apply to all components"
}