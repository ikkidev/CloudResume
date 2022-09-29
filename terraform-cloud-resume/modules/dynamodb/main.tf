resource "aws_dynamodb_table" "visitor_count" {
  name = "visitor_count"
  hash_key = "total_visit"
  billing_mode = "PAY_PER_REQUEST"

  attribute {
    name =  "total_visit"
    type = "N"
  }

  tags = var.common_tags
}