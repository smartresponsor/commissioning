param(
    [switch]$Apply
)

$ErrorActionPreference = 'Stop'
$root = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path
$src = Join-Path $root 'src'

function Get-ClassDescription([string]$relative, [string]$name) {
    if ($relative -match '^DTO/') { return "Carries typed Commissioning data for the $name application boundary and its callers." }
    if ($relative -match '^EntityInterface/') { return "Defines the public Commissioning entity contract represented by $name across persistence-aware callers." }
    if ($relative -match '^Entity/') { return "Represents persisted Commissioning state for $name records and their application lifecycle." }
    if ($relative -match '^RepositoryInterface/') { return "Defines the Commissioning persistence contract exposed by $name to application services and resolvers." }
    if ($relative -match '^Repository/') { return "Persists and queries Commissioning records through the $name Doctrine repository boundary." }
    if ($relative -match 'Interface/') { return "Defines the public Commissioning behavior contract exposed by $name to typed application collaborators." }
    if ($relative -match '^Service/') { return "Coordinates Commissioning application behavior implemented by $name across typed collaborators and boundaries." }
    if ($relative -match '^Resolver/') { return "Resolves canonical Commissioning data through $name from typed requests and available context." }
    if ($relative -match '^Calculator/') { return "Calculates canonical Commissioning amounts through $name using typed basis and rate inputs." }
    if ($relative -match '^Controller/') { return "Exposes Commissioning HTTP behavior through $name while delegating business work to typed services." }
    if ($relative -match '^Command/') { return "Exposes the Commissioning console operation implemented by $name for deterministic operational workflows." }
    if ($relative -match '^Enum/') { return "Enumerates the canonical Commissioning values represented by $name across typed application boundaries." }
    if ($relative -match '^ValueObject/') { return "Represents immutable Commissioning value semantics through $name at typed application boundaries." }
    if ($relative -match '^Event/') { return "Carries the Commissioning event payload represented by $name across synchronous application listeners." }
    if ($relative -match '^EventSubscriber/') { return "Subscribes $name to Commissioning workflow events while keeping framework integration explicit." }
    if ($relative -match '^Policy/') { return "Defines reusable Commissioning policy decisions through $name independently from persistence infrastructure." }
    if ($relative -match '^DependencyInjection/') { return "Configures the Commissioning Symfony dependency-injection surface represented by $name for host applications." }
    return "Defines the Commissioning application responsibility represented by $name within its canonical typed layer."
}

function Get-MethodDescription([string]$name) {
    if ($name -eq 'execute') { return 'Executes this Commissioning console operation and reports the resulting deterministic command status.' }
    if ($name -eq 'calculate' -or $name -like 'calculate*') { return 'Calculates the Commissioning result from the supplied typed request and configured calculation inputs.' }
    if ($name -eq 'supports') { return 'Reports whether this calculator supports the supplied canonical Commissioning rate definition.' }
    if ($name -like 'find*') { return 'Finds Commissioning records matching the supplied criteria for the calling application collaborator.' }
    if ($name -eq 'save') { return 'Persists the supplied Commissioning record through this repository persistence boundary.' }
    if ($name -like 'resolve*') { return 'Resolves canonical Commissioning data from the supplied typed request and available application context.' }
    if ($name -like 'map*') { return 'Maps the supplied external Commissioning request into its canonical typed application representation.' }
    if ($name -like 'mark*') { return 'Applies the requested Commissioning lifecycle transition and returns the resulting application state.' }
    if ($name -like 'export*') { return 'Exports canonical Commissioning settlement data through the typed application handoff contract.' }
    if ($name -like 'check*') { return 'Checks the supplied Commissioning state against this explicit application readiness contract.' }
    if ($name -like 'matches*') { return 'Evaluates whether the supplied Commissioning context satisfies the configured rule contract.' }
    if ($name -eq 'audit') { return 'Builds the Commissioning runtime audit report from configured routes and persistence metadata.' }
    if ($name -like 'seed*') { return 'Seeds deterministic Commissioning development data while preserving idempotent repository behavior.' }
    if ($name -eq 'transactional') { return 'Executes the supplied Commissioning callback within the configured persistence transaction boundary.' }
    if ($name -like 'assert*') { return 'Asserts the Commissioning application invariant represented by this contract and fails explicitly otherwise.' }
    if ($name -like 'can*') { return 'Reports whether the requested Commissioning state transition satisfies the canonical lifecycle policy.' }
    if ($name -like 'known*') { return 'Returns the canonical Commissioning states recognized by this reusable lifecycle policy.' }
    if ($name -like 'getSubscribedEvents') { return 'Returns the explicit framework event subscriptions owned by this Commissioning subscriber.' }
    return "Performs the $name operation defined by this typed Commissioning application contract."
}

function Has-AdjacentDocBlock([string]$text, [int]$index) {
    $prefix = $text.Substring(0, $index)
    $trimmed = $prefix.TrimEnd()
    return $trimmed.EndsWith('*/')
}

$changedFiles = 0
$insertedClasses = 0
$insertedMethods = 0

Get-ChildItem -Path $src -Recurse -Filter '*.php' -File | ForEach-Object {
    $path = $_.FullName
    $relative = $path.Substring($src.Length + 1).Replace('\', '/')
    $text = [System.IO.File]::ReadAllText($path)
    $original = $text
    $newline = if ($text.Contains("`r`n")) { "`r`n" } else { "`n" }

    $classPattern = '(?ms)(?<attrs>(?:^[ \t]*#\[[^\r\n]*\][ \t]*\r?\n)*)(?<indent>^[ \t]*)(?<decl>(?:final\s+|abstract\s+|readonly\s+)*(?:class|interface|trait|enum)\s+(?<name>[A-Za-z_][A-Za-z0-9_]*))'
    $classMatches = [regex]::Matches($text, $classPattern)
    for ($i = $classMatches.Count - 1; $i -ge 0; --$i) {
        $m = $classMatches[$i]
        if (Has-AdjacentDocBlock $text $m.Index) { continue }
        $name = $m.Groups['name'].Value
        $indent = $m.Groups['indent'].Value
        $desc = Get-ClassDescription $relative $name
        $doc = "$indent/**$newline$indent * $desc$newline$indent */$newline"
        $text = $text.Insert($m.Index, $doc)
        ++$insertedClasses
    }

    $methodPattern = '(?ms)(?<attrs>(?:^[ \t]*#\[[^\r\n]*\][ \t]*\r?\n)*)(?<indent>^[ \t]*)(?<mods>(?:(?:public|protected|private|static|final|abstract)\s+)*)function\s+&?\s*(?<name>[A-Za-z_][A-Za-z0-9_]*)\s*\('
    $methodMatches = [regex]::Matches($text, $methodPattern)
    for ($i = $methodMatches.Count - 1; $i -ge 0; --$i) {
        $m = $methodMatches[$i]
        $name = $m.Groups['name'].Value
        $mods = $m.Groups['mods'].Value
        if ($mods -match '\bprivate\b') { continue }
        if ($name.StartsWith('__') -or $name -in @('__construct', '__destruct')) { continue }
        if ($name -match '^(get|set|is|has)[A-Z_]') { continue }
        if (Has-AdjacentDocBlock $text $m.Index) { continue }

        $indent = $m.Groups['indent'].Value
        $desc = Get-MethodDescription $name
        $doc = "$indent/**$newline$indent * $desc$newline$indent */$newline"
        $text = $text.Insert($m.Index, $doc)
        ++$insertedMethods
    }

    if ($text -ne $original) {
        ++$changedFiles
        if ($Apply) {
            [System.IO.File]::WriteAllText($path, $text, [System.Text.UTF8Encoding]::new($false))
        }
    }
}

Write-Output ("files={0}; classes={1}; methods={2}; apply={3}" -f $changedFiles, $insertedClasses, $insertedMethods, [bool]$Apply)

