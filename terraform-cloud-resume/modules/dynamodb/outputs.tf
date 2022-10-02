output "table_arn" {
  description = "ARN of the dynamoDB table"

  value = aws_dynamodb_table.visitor_count.arn
}