{ pkgs ? import <nixpkgs> {} }:

pkgs.buildEnv {
  name = "env";
  paths = [
    pkgs.php
    pkgs.nodejs
    pkgs.pnpm
    (pkgs.composer.overrideAttrs (old: {
      installPhase = old.installPhase + ''
        mv $out/LICENSE $out/LICENSE-composer
      '';
    }))
  ];
}

