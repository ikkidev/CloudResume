resource "aws_dynamodb_table" "visitor_count" {
  name = "visitor_count"
  hash_key = "domain"
  billing_mode = "PAY_PER_REQUEST"

  attribute {
    name = "domain"
    type = "S"
  }

# We don't need to define every attribute upfront when creating the dynamoDB table
# This terraform module only defines the key schema and index for the table
# The total_visit attribute will be created by the lambda function
#  attribute {
#    name =  "total_visit"
#    type = "N"
#  }

  tags = var.common_tags
}