
## Command structure
```
<script> <command> <flags[]> <arguments[]>
./index.php problems:list --unresolved 2 10
```

## Program structure
- [ ] split script into subcommands (problem:list, problem:check)
- [ ] data and logic should always be in different sources
- [ ] don't use global state - delegate such stuff to an external entity (ex: class)
- [ ] extract external/interactive (ex: console) interations into something isolated (echo, print, readline)
```
# phpunit test case example for wrong passord in passman

$ioMock = $this->createMock(IO::class);

// prepare
$ioMock->method('input')->willReturn('wrongPassword');

// assertion
$ioMock->method('write')->expect('Wrong password.');

// execute
$application = new Application(...);

$application->handle('passord:list');
```
- [ ] add a command to create problems
- [ ] add command to submit solutions (not check, but submit, save into a file) (solution:submit)
- [ ] add possibility to list solutions (solution:list) and select one for a run (solution:run)
- [ ] don't use stdClass objects
- [ ] store problems in a json file

## Later
- [ ] add categories of problems (one folder - one category, one file - one problem)
