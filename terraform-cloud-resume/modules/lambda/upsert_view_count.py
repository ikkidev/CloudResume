import json
import boto3  # import the boto3 module

dynamodb = boto3.resource('dynamodb')  # get the DynamoDB resource
table = dynamodb.Table('visitor_count')

'''
Use the following test event structure to test the lambda function from the aws console
{
"total_visit": 4
}
'''

def lambda_handler(event, context):
    response = table.put_item(Item=event)
    print(f"Response is {response}")
    return {
        'statusCode': 200,
        'body': json.dumps({"result": 'Updated entry successfully!', "response": response})
    }
