<?php

enum CommandEnum: string
{
    case PROBLEMS_LIST = 'problems:list';
    case SOLUTION_RUN = 'solution:run';
    case SOLUTION_SUBMIT = 'solution:submit';
}
