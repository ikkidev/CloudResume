variable "domain_name" {
  type = string
  description = "The domain name for the website"
}

variable "common_tags" {
  type = map(string)
  description = "The common tag to apply to all components"
}


variable "aws_profile" {
  type = string
  description = "The AWS profile to use for deploying AWS resources"
}

variable "main_zone_id" {
  type = string
  description = "Zone id of the Route53 record"
}