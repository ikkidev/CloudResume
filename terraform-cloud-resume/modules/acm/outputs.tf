output "cert_validation_certificate_arn" {
  description = "The ARN of the certificate that is being validated."
  value = aws_acm_certificate_validation.cert_validation.certificate_arn
}

output "ssl_certificate_domain_validation_options" {
  description = "AWS ACM ssl certificate option to use to validate domain"
  value = aws_acm_certificate.ssl_certificate.domain_validation_options
}