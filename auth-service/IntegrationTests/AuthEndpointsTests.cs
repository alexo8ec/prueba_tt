using System.Net.Http;
using System.Net.Http.Json;
using System.Threading.Tasks;
using AuthService;
using AuthService.DTOs;
using Microsoft.AspNetCore.Mvc.Testing;
using Xunit;

namespace AuthService.IntegrationTests
{
    public class AuthEndpointsTests : IClassFixture<WebApplicationFactory<Program>>
    {
        private readonly HttpClient _client;

        public AuthEndpointsTests(WebApplicationFactory<Program> factory)
        {
            _client = factory.CreateClient();
        }

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
    }

    public class LoginResponseDto
    {
        public string Token { get; set; }
    }
}
