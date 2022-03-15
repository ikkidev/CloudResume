resource "aws_dynamodb_table" "visitor_count" {
  name = "visitor_count"
  hash_key = "hostname"
  billing_mode = "PAY_PER_REQUEST"

  attribute {
    name =  "hostname"
    type = "S"
  }

  tags = var.common_tags
}