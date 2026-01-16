{pkgs}: {
  channel = "stable-24.05";
  packages = [
    pkgs.php82,
    pkgs.composer,
    pkgs.nodejs_18
  ];
  idx.extensions = [
    "bmewburn.vscode-intelephense-client",
    "dbaeumer.vscode-eslint"
  ];
  idx.previews = {
    previews = {
      web = {
        command = [
          "php",
          "artisan",
          "serve",
          "--host=0.0.0.0",
          "--port=$PORT"
        ];
        manager = "web";
      };
    };
  };
}
