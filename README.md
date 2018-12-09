# Powershell_Sockets
Programa de acesso remoto via Sockets TCP.
<br />
O intuito da criação deste programa é promover uma plataforma parecida com o __SSH__ do Linux no Windows.
<br />
## Comandos (Cliente)
- __testnotepad__ &nbsp;&nbsp;&nbsp; Abre 10 páginas do notepad com a frase "Testando notepad !!!"
- __setvolume__ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Modifica o volume do servidor, exemplo: __setvolume 20__ deixa o volume do servidor em 20
- __playmusic__ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Toca uma música em plano de fundo. O comando deve conter o diretório completo da música, exemplo: __playmusic C:\Programas\musica.mp3__
- __send__ &nbsp;&nbsp; Envia um arquivo da máquina local para o servidor. O diretório do arquivo local deve ser completo, além disso o segundo parâmetro é o nome que o arquivo terá no servidor (com ou sem o diretório completo). Exemplo: __send C:\Programas\teste.mp3 teste.mp3__
<br />
&rarr; Demais comandos serão interpretados como comandos no '__'Prompt'__'. Exemplo: __mkdir__ cria um diretório no servidor.
