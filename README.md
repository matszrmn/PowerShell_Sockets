# Powershell_Sockets

Programa de acesso remoto via Sockets TCP.
<br />
O intuito da criação deste programa é promover uma plataforma parecida com o __SSH__ do Linux no Windows.
<br />

## Comandos (Cliente)

- __testnotepad__
  <p> Abre 10 páginas do notepad com a frase "Testando notepad !!!" </p>

- __setvolume__
  <p> Modifica o volume do servidor, exemplo: &nbsp; <b>setvolume 20</b> &nbsp; deixa o volume do servidor em 20 </p>

- __playmusic__
  <p> Toca uma música do servidor em plano de fundo. O comando deve conter o diretório completo da música. Exemplo: <br />
  <b>playmusic &nbsp; C:\Programas\musica.mp3</b>
  
- __send__
  <p> Envia um arquivo da máquina local para o servidor. O diretório do arquivo local deve ser completo, além disso o segundo parâmetro é o nome que o arquivo terá no servidor (com ou sem o diretório completo). Exemplo: <br />
  <b>send &nbsp; C:\Programas\teste.mp3 &nbsp; teste.mp3</b>

<br />
&rarr; Demais comandos serão interpretados como comandos do <b>Prompt</b>. Exemplo: <b>mkdir</b> cria um diretório no servidor.
