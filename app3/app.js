let keycloakConfig = {
  "realm": "myrealm",
  "auth-server-url": "http://127.0.0.1:8080",
  "ssl-required": "external",
  "resource": "web-app3",
  "credentials": {
      "secret": "90d014fa-89bf-45b5-ab87-bcd8a9028c7f"
  },
  "confidential-port": 0,
  "enable-cors": true,
  "clientId": "web-app3"
};
window.keycloak = new Keycloak(keycloakConfig);
