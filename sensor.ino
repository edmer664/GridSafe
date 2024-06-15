
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266HTTPClient.h>

/**
 * CONFIGURATION
 * D0 = LED
 * D1 = PUSH BUTTON
 */

WiFiClient wifiClient;

const char *ssid = "HUAWEI-2.4G-3Mjv";
const char *password = "tsWt9Y4A";
const char *serverName = "http://192.168.100.3:8000/api/log";
bool anomaly = false;

struct SensorData
{
    float voltage;
    float ampere;
    float equipment_temp;
    float ambient_temp;
};
// d1:5 = button

int BUTTON = D1;
int LED = D2;

void setup()
{
    Serial.begin(115200);
    pinMode(BUTTON, INPUT);
    pinMode(LED, OUTPUT);

    WiFi.begin(ssid, password);
    Serial.print("Connecting to WiFi..");
    while (WiFi.status() != WL_CONNECTED)
    {
        Serial.print(".");
        delay(500);
    }
    Serial.println("Connected!");
    Serial.println("IP address: ");
    Serial.println(WiFi.localIP());
}



// Listen for button press
void buttonPressed()
{
    Serial.println("Button pressed!");
    anomaly = !anomaly;
    if (anomaly)
    {
        turnOnLED();
    }
    else
    {
        turnOffLED();
    }
}

void turnOnLED()
{
    digitalWrite(LED, HIGH);
}

void turnOffLED()
{
    digitalWrite(LED, LOW);
}

void apiLog(String message)
{
    HTTPClient http;

    // Set the server address
    http.begin(wifiClient, serverName);
    http.addHeader("Content-Type", "application/json");

    // Prepare the JSON payload
    String postData = "{\"message\": \"" + message + "\"}";

    // Send the HTTP POST request
    int httpResponseCode = http.POST(postData);

    if (httpResponseCode > 0)
    {
        Serial.print("HTTP POST request successful: ");
        Serial.println(httpResponseCode);
        String response = http.getString();
        Serial.println(response);
    }
    else
    {
        Serial.print("Error sending POST request: ");
        Serial.println(httpResponseCode);
    }

    http.end();
    delay(1000);
}



SensorData generateSensorData(bool has_anomaly)
{

    SensorData data;

    // Normal operating ranges
    float normal_voltage = 230.0;       // in volts
    float normal_ampere = 10.0;         // in amperes
    float normal_equipment_temp = 75.0; // in degrees Celsius
    float normal_ambient_temp = 25.0;   // in degrees Celsius

    // Anomaly multipliers
    float anomaly_multiplier = 1.5;

    // Generate base data
    data.voltage = normal_voltage + random(-10, 10) * 0.1;             // +/- 1 volt fluctuation
    data.ampere = normal_ampere + random(-5, 5) * 0.1;                 // +/- 0.5 ampere fluctuation
    data.equipment_temp = normal_equipment_temp + random(-5, 5) * 0.1; // +/- 0.5 degrees fluctuation
    data.ambient_temp = normal_ambient_temp + random(-2, 2) * 0.1;     // +/- 0.2 degrees fluctuation

    // Introduce anomalies if has_anomaly is true
    if (has_anomaly)
    {
        data.voltage *= anomaly_multiplier;
        data.ampere *= anomaly_multiplier;
        data.equipment_temp *= anomaly_multiplier;
        data.ambient_temp *= anomaly_multiplier;
    }

    return data;
}

SensorData generateGenerationSensorData(bool is_low_supply)
{
    SensorData data;

    // Normal operating ranges
    float normal_voltage = 240.0;       // in volts
    float normal_ampere = 15.0;         // in amperes
    float normal_equipment_temp = 60.0; // in degrees Celsius
    float normal_ambient_temp = 30.0;   // in degrees Celsius

    // Low supply multipliers
    float low_supply_multiplier = 0.7;

    // Generate base data
    data.voltage = normal_voltage + random(-5, 6) * 0.1;               // +/- 0.5 volt fluctuation
    data.ampere = normal_ampere + random(-3, 4) * 0.1;                 // +/- 0.3 ampere fluctuation
    data.equipment_temp = normal_equipment_temp + random(-2, 3) * 0.1; // +/- 0.2 degrees fluctuation
    data.ambient_temp = normal_ambient_temp + random(-1, 2) * 0.1;     // +/- 0.1 degrees fluctuation

    // Adjust for low supply conditions
    if (is_low_supply)
    {
        data.voltage *= low_supply_multiplier;
        data.ampere *= low_supply_multiplier;
    }

    return data;
}

bool sendSensorData(SensorData sensor_data, bool is_generation, String node_id)
{
    HTTPClient http;
    String serverName = "http://192.168.100.3:8000/api/send_data";
    http.begin(wifiClient,serverName);
    http.addHeader("Content-Type", "application/json");

    String postData = "{\"node_id\": \"" + node_id + "\", \"is_generation\": " + String(is_generation) + ", \"voltage\": " + String(sensor_data.voltage) + ", \"ampere\": " + String(sensor_data.ampere) + ", \"equipment_temp\": " + String(sensor_data.equipment_temp) + ", \"ambient_temp\": " + String(sensor_data.ambient_temp) + "}";

    int httpResponseCode = http.POST(postData);

    if (httpResponseCode > 0)
    {
        Serial.print("HTTP POST request successful: ");
        Serial.println(httpResponseCode);
        String response = http.getString();
        Serial.println(response);
    }
    else
    {
        Serial.print("Error sending POST request: ");
        Serial.println(httpResponseCode);
    }

    http.end();
    delay(1000);

    return httpResponseCode > 0;
}


void loop()
{
    // log the pin state
    apiLog("button: " + String(digitalRead(BUTTON)));

    // log the anomaly state
    apiLog("Anomaly: " + String(anomaly));

    // Check if the button is pressed
    if (digitalRead(BUTTON) == HIGH)
    {
        buttonPressed();
    }
    else
    {
        apiLog("Button not pressed!");
    }

    // GEN NODE LISTS
    String gen_node_ids[] = {"NODE_GEN01", "NODE_GEN02", "NODE_GEN03"};

    // STATIC NODE LISTS
    String node_ids[] = {"NODE01", "NODE02", "NODE03", "NODE04", "NODE05", "NODE06", "NODE07", "NODE08", "NODE09", "NODE10"};

    // Send sensor data for each node
    for (int i = 0; i < 3; i++)
    {
        SensorData sensor_data = generateGenerationSensorData(anomaly);
        sendSensorData(sensor_data, true, gen_node_ids[i]);
    }

    for (int i = 0; i < 10; i++)
    {
        SensorData sensor_data = generateSensorData(anomaly);
        sendSensorData(sensor_data, false, node_ids[i]);
    }

    delay(1000);
}