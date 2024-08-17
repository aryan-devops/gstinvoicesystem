import requests

# Define the API endpoint URL
api_url = "https://cleartax.in/commonapi/v1.0/tpstatus"

# Define the GSTIN and action you want to include in the request data
gstin = "22GEIPP1287J1Z6"
action = "POST"

# Create a dictionary with the data to be sent in the request body
data = {"gstin": gstin, "action": action}

# Make a POST request to the API
response = requests.post(api_url, json=data, headers={"Content-Type": "application/json"})
# Check if the request was successful (status code 200)
if response.status_code == 200:
    # Parse the JSON response
    data = response.json()
    # Print the response data
    print(data)
else:
    # If the request was not successful, print an error message
    print("Error:", response.status_code, response.text)
