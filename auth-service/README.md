# AuthService

Este servicio implementa la autenticación y generación de JWT para el sistema.

## Pruebas de integración

El flujo crítico de negocio (registro y login) se prueba en:

`IntegrationTests/AuthEndpointsTests.cs`

### Ejecutar pruebas

Desde la carpeta `auth-service`:

```
dotnet test
```

### Ejemplo de test incluido

```csharp
[Fact]
public async Task FlujoCompleto_Registro_Login_OK()
{
    // 1. Registro
    var email = $"test_{System.DateTime.Now.Ticks}@demo.com";
    var registerDto = new RegisterDto
    {
        Email = email,
        Password = "Test1234!",
        Nombre = "Test",
        Apellido = "User",
        Telefono = "123456789",
        Direccion = "Test Address"
    };
    var registerResponse = await _client.PostAsJsonAsync("/api/auth/register", registerDto);
    Assert.True(registerResponse.IsSuccessStatusCode);

    // 2. Login
    var loginDto = new LoginDto
    {
        Email = email,
        Password = "Test1234!"
    };
    var loginResponse = await _client.PostAsJsonAsync("/api/auth/login", loginDto);
    Assert.True(loginResponse.IsSuccessStatusCode);
    var loginContent = await loginResponse.Content.ReadFromJsonAsync<LoginResponseDto>();
    Assert.False(string.IsNullOrEmpty(loginContent?.Token));
}
```

---

Para más detalles, revisa el archivo de test en `IntegrationTests/AuthEndpointsTests.cs`.
