terraform {
  required_providers {
    aws = {
      source = "hashicorp/aws"
      version = "= 3.75.2"
    }
  }

  required_version = "~> 1.1.3"
}