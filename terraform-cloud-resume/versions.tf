terraform {
  required_providers {
    aws = {
      source = "hashicorp/aws"
      version = "~> 3.72"
    }
  }

  required_version = "~> 1.1.3"
}