{ pkgs ? import <nixpkgs> {} }:

pkgs.buildEnv {
  name = "custom-laravel-env";
  paths = [
    pkgs.php
    pkgs.nodejs
    pkgs.pnpm
    (pkgs.composer.overrideAttrs (old: {
      postInstall = ''
        mv $out/LICENSE $out/LICENSE-composer
      '';
    }))
  ];
}

