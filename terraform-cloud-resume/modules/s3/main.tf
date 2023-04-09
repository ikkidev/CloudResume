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
  bucket   = "www.${var.bucket_name}"
  tags = var.common_tags
}

resource "aws_s3_bucket_cors_configuration" "www_bucket" {
  provider = aws.s3
  bucket = aws_s3_bucket.www_bucket.id
  cors_rule {
    allowed_headers = ["Authorization", "Content-Length"]
    allowed_methods = ["GET", "POST"]
    allowed_origins = ["https://www.${var.domain_name}"]
    max_age_seconds = 3000
  }
}

resource "aws_s3_bucket_policy" "www_bucket" {
  provider = aws.s3
  bucket = aws_s3_bucket.www_bucket.id
  policy = templatefile("${path.module}/templates/s3-policy.json", { bucket = "www.${var.bucket_name}" })
}


resource "aws_s3_bucket_ownership_controls" "www_bucket" {
  provider = aws.s3
  bucket = aws_s3_bucket.www_bucket.id
  rule {
    object_ownership = "BucketOwnerPreferred"
  }
}

resource "aws_s3_bucket_public_access_block" "www_bucket" {
  provider = aws.s3
  bucket = aws_s3_bucket.www_bucket.id

  block_public_acls       = false
  block_public_policy     = false
  ignore_public_acls      = false
  restrict_public_buckets = false
}

resource "aws_s3_bucket_acl" "www_bucket" {
  depends_on = [
    aws_s3_bucket_ownership_controls.www_bucket,
    aws_s3_bucket_public_access_block.www_bucket,
  ]

  provider = aws.s3
  bucket = aws_s3_bucket.www_bucket.id
  acl    = "public-read"
}

resource "aws_s3_bucket_website_configuration" "www_bucket" {
  provider = aws.s3
  bucket = aws_s3_bucket.www_bucket.id
  index_document {
    suffix = "index.html"
  }
}

# Upload latest resume to s3 bucket
resource "aws_s3_object" "upload_static_web_files" {
  for_each = fileset("${path.module}/../../../Webpage/src/", "**")
  provider = aws.s3
  bucket = aws_s3_bucket.www_bucket.id

  key    = each.value
  source       = "${path.module}/../../../Webpage/src/${each.value}"
  etag         = filemd5("${path.module}/../../../Webpage/src/${each.value}")
  content_type = lookup(local.mime_types, split(".", each.value)[length(split(".", each.value)) - 1])
}

# S3 bucket for redirecting non-www to www.
resource "aws_s3_bucket" "root_bucket" {
  provider = aws.s3
  bucket   = var.bucket_name
  tags     = var.common_tags
}

resource "aws_s3_bucket_policy" "root_bucket" {
  provider = aws.s3
  bucket = aws_s3_bucket.root_bucket.id
  policy = templatefile("${path.module}/templates/s3-policy.json", { bucket = var.bucket_name })
}

resource "aws_s3_bucket_ownership_controls" "root_bucket" {
  provider = aws.s3
  bucket = aws_s3_bucket.root_bucket.id
  rule {
    object_ownership = "BucketOwnerPreferred"
  }
}

resource "aws_s3_bucket_public_access_block" "root_bucket" {
  provider = aws.s3
  bucket = aws_s3_bucket.root_bucket.id

  block_public_acls       = false
  block_public_policy     = false
  ignore_public_acls      = false
  restrict_public_buckets = false
}

resource "aws_s3_bucket_acl" "root_bucket" {
  depends_on = [
    aws_s3_bucket_ownership_controls.root_bucket,
    aws_s3_bucket_public_access_block.root_bucket,
  ]
  provider = aws.s3
  bucket = aws_s3_bucket.root_bucket.id
  acl    = "public-read"
}

resource "aws_s3_bucket_website_configuration" "root_bucket" {
  provider = aws.s3
  bucket = aws_s3_bucket.root_bucket.id

  redirect_all_requests_to {
    host_name = "https://www.${var.domain_name}"
  }
}