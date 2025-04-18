<?php

enum CommandEnum: string
{
    case PROBLEMS_LIST = 'problems:list';
    case SOLUTION_RUN = 'solution:run';
    case SOLUTION_SUBMIT = 'solution:submit';
    case SOLUTION_LIST = 'solution:list';
    case SOLUTION_CHECK = 'solution:check';
}
