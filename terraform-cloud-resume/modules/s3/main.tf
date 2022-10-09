locals {
  mime_types = {
    htm  = "text/html"
    html = "text/html"
    css  = "text/css"
    ttf  = "font/ttf"
    js   = "application/javascript"
    map  = "application/javascript"
    json = "application/json"
    png  = "image/png"
  }
}

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

# Upload latest resume to s3 bucket
resource "aws_s3_bucket_object" "upload_static_web_files" {
  for_each = fileset("${path.module}/../../../Webpage/src/","**")
  bucket = aws_s3_bucket.www_bucket.id
  provider = aws.s3

  key = each.value
  source = "${path.module}/../../../Webpage/src/${each.value}"
  etag = filemd5("${path.module}/../../../Webpage/src/${each.value}")
  content_type = lookup(local.mime_types, split(".", each.value)[length(split(".", each.value)) - 1])
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