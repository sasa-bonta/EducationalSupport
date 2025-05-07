
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
- [x] add command to submit solutions (not check, but submit, save into a file) (solution:submit)
- [ ] add possibility to check solution (solution:check)
- [1/2] add possibility to list solutions (solution:list) and select one for a run (solution:run)
- [x] don't use stdClass objects
- [x] store problems in a json file
- [ ] add examples of commands

## Later
- [ ] add categories of problems (one file - one problem)

### (TO DO) psr-4 load classes, autoloader (initially with require, then other branch autoload psr-4)
Написать набор классов.
- Application
  - registerCommand()
  - run()
- AbstractCommand
  - handle()
- ConcretCommand extends AbstractCommand

index:
new application
application->regComm
application->regComm
application->regComm

application->run(arguments)

---

### Later (NOT TO DO) TestRunner, TestCase, Problem, Solution // my classes
```ts
Problem: {
    task: string
    testCases: TestCase[]
}
```
