# S3 bucket for website.
resource "aws_s3_bucket" "www_bucket" {
  provider = aws.s3
  bucket = "www.${var.bucket_name}"
  acl = "public-read"
  policy = templatefile("${path.module}/templates/s3-policy.json", { bucket = "www.${var.bucket_name}" })

  cors_rule {
    allowed_headers = ["Authorization", "Content-Length"]
    allowed_methods = ["GET", "POST"]
    allowed_origins = ["https://www.${var.domain_name}"]
    max_age_seconds = 3000
  }

  website {
    index_document = "index.html"
  }

  tags = var.common_tags
}

# S3 bucket for redirecting non-www to www.
resource "aws_s3_bucket" "root_bucket" {
  provider = aws.s3
  bucket = var.bucket_name
  acl = "public-read"
  policy = templatefile("${path.module}/templates/s3-policy.json", { bucket = var.bucket_name })

  website {
    redirect_all_requests_to = "https://www.${var.domain_name}"
  }

  tags = var.common_tags
}