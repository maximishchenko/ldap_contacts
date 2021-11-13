#
# Формат запуска из командной строки:
# powershell -File путь_к_скрипту_ps1 -ini "путь_к_ini_файлу"
# Пример:
# powershell -File d:\scripts\sendmailfromtemplate\SendMailFromTemplate.ps1 -ini "d:\scripts\sendmailfromtemplate\crd.ini"
#

param (    
    [string]$ini = "crd.ini"
)


$HeaderString = "Сведения о желающих пройти вакцинацию против коронавируса по состоянию на"

### functions

# Получает содержимое ini-файла
function Get-IniFile {    
    param(
        [parameter(Mandatory = $true)] [string] $filePath,
        [string] $anonymous = 'NoSection',
        [switch] $comments,
        [string] $commentsSectionsSuffix = '_',
        [string] $commentsKeyPrefix = 'Comment'
    )

    $ini = @{}
    switch -regex -file ($filePath) {
        "^\[(.+)\]$" {
            # Section
            $section = $matches[1]
            $ini[$section] = @{}
            $CommentCount = 0
            if ($comments) {
                $commentsSection = $section + $commentsSectionsSuffix
                $ini[$commentsSection] = @{}
            }
            continue
        }

        "^(;.*)$" {
            # Comment
            if ($comments) {
                if (!($section)) {
                    $section = $anonymous
                    $ini[$section] = @{}
                }
                $value = $matches[1]
                $CommentCount = $CommentCount + 1
                $name = $commentsKeyPrefix + $CommentCount
                $commentsSection = $section + $commentsSectionsSuffix
                $ini[$commentsSection][$name] = $value
            }
            continue
        }

        "^(.+?)\s*=\s*(.*)$" {
            # Key
            if (!($section)) {
                $section = $anonymous
                $ini[$section] = @{}
            }
            $name, $value = $matches[1..2]
            $ini[$section][$name] = $value
            continue
        }
    }

    return $ini
}

function Test-FileLock {
    param (
        [parameter(Mandatory=$true)][string]$Path
    )

    $oFile = New-Object System.IO.FileInfo $Path

    if ((Test-Path -Path $Path) -eq $false) {
        return $false
    }

    try {
        $oStream = $oFile.Open([System.IO.FileMode]::Open, [System.IO.FileAccess]::ReadWrite, [System.IO.FileShare]::None)

        if ($oStream) {
            $oStream.Close()
        }
        return $false
    } catch {
        return $true
    }
}

### main
$config = Get-Inifile $ini

$User = $config.account.username
$Password = $config.account.password
$SMTPServer = $config.account.server
$SMTPPort = $config.account.port
$SMTPSsl = $config.account.ssl

$From = $config.message.from
$To = $config.message.to
$Subject = $config.message.subject
$Body = $config.message.body
$Cc = $config.message.cc
$Bcc = $config.message.bcc
$Attachment = $config.message.attachment

$CurrentDate = Get-Date -Format dd.MM.yyyy


#Get-Process excel –ea 0 | Where-Object { $_.MainWindowTitle –like $Attachment } | Stop-Process


$DateHeaderString = $HeaderString + " " + $CurrentDate
$excel = New-Object -ComObject Excel.Application
$excel.Visible = $true
$workbook = $excel.Workbooks.Open($Attachment)
$workbook.ActiveSheet.Cells.Item(1,1) = $DateHeaderString
$workbook.Save()
$excel.Quit()

Start-Sleep -Seconds 5
 
$cred=New-Object -TypeName System.Management.Automation.PSCredential -ArgumentList $User, (ConvertTo-SecureString -String $Password -AsPlainText -Force)

$Subject = $CurrentDate + ". " + $Subject
$SMTPMessage = New-Object System.Net.Mail.MailMessage($From,$To,$Subject,$Body)
if (-not ([string]::IsNullOrEmpty($Cc)))
{
    $SMTPMessage.cc.Add($Cc)
}
if (-not ([string]::IsNullOrEmpty($Bcc)))
{
    $SMTPMessage.bcc.Add($Bcc)
}
$SMTPMessage.Attachments.Add($Attachment)
$SMTPClient = New-Object Net.Mail.SmtpClient($SMTPServer, $SMTPPort) 
$SMTPClient.EnableSsl = $False
$SMTPClient.Credentials = New-Object System.Net.NetworkCredential($cred.UserName, $cred.Password); 
$SMTPClient.Send($SMTPMessage)

$SMTPMessage.Dispose();