# mono-xdebug-client

GUI Xdebug client written in PHP (well, Phalanger). Work in progress.

To build:
1. Install mono-devel, mono-xbuild, monodevelop 
2. Install Phalanger (DEVSENSE/Phalanger). If you're doing it from git checkout, useful fixes are here: DEVSENSE/Phalanger#61
3. Make sure you have phalanger xbuild tasks (do `sudo xbuild Source/Phalanger.CompilerTask/Phalanger.CompilerTask.csproj /p:Configuration=Release /p:TreatWarningsAsErrors=false /p:ForceMSBuildDeploy=true /t:AfterBuild`)
4. Build with `xbuild` in the top-level project directory
