import pytest
from moto import mock_dynamodb
import boto3
from upsert_visitor_count import update_table


@mock_dynamodb
def test_update_table():
    mock_table = boto3.resource('dynamodb')
    domain = "test.com"
    table = mock_table.create_table(
        TableName="visitor_count",
        KeySchema=[{'AttributeName': 'domain','KeyType': 'HASH'}],
        AttributeDefinitions=[{'AttributeName': 'domain','AttributeType': 'S'}],
        ProvisionedThroughput={"ReadCapacityUnits": 5, "WriteCapacityUnits": 5},
    )
    response = update_table(table, domain)

    visitor_count = int(response.get("Attributes").get("visitor_count"))
    assert visitor_count == 1
