import json
import logging
import boto3  # import the boto3 module
from botocore.exceptions import ClientError

logger = logging.getLogger(__name__)
dynamodb = boto3.resource('dynamodb')  # get the DynamoDB resource
table = dynamodb.Table('visitor_count')

'''
Use the following test event structure to test the lambda function from the aws console
{
  "body": "{\"domain\": \"ikkidev.com\"}"
}

Sample Response:
{
  "domain": "ikkidev.com",
  "visitor_count": 4
}

'''

def lambda_handler(event, context):

    event_body = json.loads(event['body'])
    domain = event_body['domain']

    try:
        response = table.update_item(
            Key={'domain': domain},
            UpdateExpression="ADD visitor_count :value",
            ExpressionAttributeValues={':value': 1},
            ReturnValues="ALL_NEW")
    except ClientError as err:
        logger.error(
            "Couldn't update visitor count for domain %s in table visitor_count. Here's why: %s: %s",
            domain,
            err.response['Error']['Code'], err.response['Error']['Message'])
        raise
    else:
        return response['Attributes']
