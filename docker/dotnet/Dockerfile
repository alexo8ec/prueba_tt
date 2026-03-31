FROM mcr.microsoft.com/dotnet/aspnet:8.0 AS base
WORKDIR /app
EXPOSE 5001

COPY ./ssl /https
ENV ASPNETCORE_URLS=https://+:5001
ENV ASPNETCORE_Kestrel__Certificates__Default__Path=/https/server.pfx
ENV ASPNETCORE_Kestrel__Certificates__Default__Password=YourCertPassword

FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /src
COPY ../../auth-service/AuthService ./AuthService
COPY ../../auth-service/AuthService.sln ./
RUN dotnet publish ./AuthService/AuthService.csproj -c Release -o /app/publish

FROM base AS final
WORKDIR /app
COPY --from=build /app/publish .
ENTRYPOINT ["dotnet", "AuthService.dll"]
