import json
import logging
import boto3  # import the boto3 module
from botocore.exceptions import ClientError

logger = logging.getLogger(__name__)

"""
Lambda function that updates and return the number of visitor counts to our website.
Sample Response:
{
  "visitor_count": 4
}
"""


def get_visitor_count(event, context):
    dynamodb = boto3.resource('dynamodb')  # get the DynamoDB resource
    table = dynamodb.Table('visitor_count')
    domain = 'ikkidev.com'
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
        visitor_count = int(response.get("Attributes").get("visitor_count"))
        return {
            "statusCode": 200,
            "body": json.dumps({"visitor_count": visitor_count}),
        }
