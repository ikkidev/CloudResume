output "main_zone_id" {
  description = "The main zone id where the DNS record is hosted"
  value = aws_route53_zone.main.zone_id
}