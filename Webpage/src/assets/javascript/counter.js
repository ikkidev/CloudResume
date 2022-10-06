function visitorCount() {
    visitCount = document.getElementById('visitCount');
    apiURL = 'https://1x6omz0f55.execute-api.ca-central-1.amazonaws.com/serverless_lambda_stage/api/v1/visitor_count'
    fetch( apiURL ).then((response) =>
        response.json().then((data) => visitCount.innerHTML = data.visitor_count)
    );
}

window.onload = visitorCount();