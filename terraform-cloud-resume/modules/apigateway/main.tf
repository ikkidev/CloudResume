resource "aws_apigatewayv2_api" "apigw" {
  name          = "serverless_lambda_gw"
  protocol_type = "HTTP"
  cors_configuration {
    allow_origins = ["*"]
  }
}

resource "aws_apigatewayv2_stage" "lambda" {
  api_id = aws_apigatewayv2_api.apigw.id

  name        = "serverless_lambda_stage"
  auto_deploy = true

  access_log_settings {
    destination_arn = aws_cloudwatch_log_group.api_gw.arn

    format = jsonencode({
      requestId               = "$context.requestId"
      sourceIp                = "$context.identity.sourceIp"
      requestTime             = "$context.requestTime"
      protocol                = "$context.protocol"
      httpMethod              = "$context.httpMethod"
      resourcePath            = "$context.resourcePath"
      routeKey                = "$context.routeKey"
      status                  = "$context.status"
      responseLength          = "$context.responseLength"
      integrationErrorMessage = "$context.integrationErrorMessage"
    }
    )
  }
}

resource "aws_apigatewayv2_integration" "visitor_count" {
  api_id = aws_apigatewayv2_api.apigw.id

  integration_uri    = var.lambda_invoke_arn
  integration_type   = "AWS_PROXY"
  integration_method = "POST"
}

resource "aws_apigatewayv2_route" "hello_world" {
  api_id = aws_apigatewayv2_api.apigw.id

  route_key = "GET /api/v1/visitor_count"
  target    = "integrations/${aws_apigatewayv2_integration.visitor_count.id}"
}

resource "aws_cloudwatch_log_group" "api_gw" {
  name = "/aws/api_gw/${aws_apigatewayv2_api.apigw.name}"

  retention_in_days = 30
}

resource "aws_lambda_permission" "api_gw" {
  statement_id  = "AllowExecutionFromAPIGateway"
  action        = "lambda:InvokeFunction"
  function_name = var.lambda_function_name
  principal     = "apigateway.amazonaws.com"

  source_arn = "${aws_apigatewayv2_api.apigw.execution_arn}/*/*"
}