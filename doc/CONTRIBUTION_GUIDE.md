TO DO AND CO - CONTRIBUTION GUIDE
========

## Get started

First, to install project, you can following instructions in [this](https://github.com/Kaloss38/P8_to_do_and_co#readme) README 

Then, if you need to add your contribution to this repository, you must to create a new branch :

~~~~
git branch myNewBranch
~~~~

Finally, switch on your new branch
~~~~
git checkout myNewBranch
~~~~

## Add your files to staging area

Once all changes are done, you can add your files in staging area
~~~~
git add myFile.php myFile2.php
~~~~
Or, you can add all of your files (Be careful of untracked files)
~~~~
git add .
~~~~

You can see all files changes with this command : `console doctrine:database:create`

## Commit and push

After you have add your files, commit them like that:
~~~~
git commit -m "Here a description of my changes"
~~~~
To finish, push new changes on repository
~~~~
git push
~~~~

## Pull request and merging

### a pull request need a reviewer
Once you have push your code, create a pull request on project repository. 
Don't forget to add your reviewer on your pull request, the reviewer will validate your changes before you can merge all files on main branch.

### merging your files
When reviewer has validate your changes, you can merge on main branch. You can do that on github interface else in command bash :

First, switch on branch main 
~~~~
git checkout main
~~~~
Then merge your branch
~~~~
git merge myNewBranch
~~~~

## Conventions

- The main production branch is named "main". Changes should only br directly merged to it in case of hotfixes. In most other cases, changes should be merged into the current release branch.

- Always create a new branch if you need to create new functionnality, name your branch related to your new functionnality
~~~~
git branch authentication
~~~~ 

- Too, commit should be named with a description of you work :
~~~~
git commit -m "Implement authentication, add UserController and User entity"
~~~~

- All your code (variables, methods, remark) must be in English

- Keep logic in your class, controllers and methods naming : 
```
example for naming an article controller :

class ArticleController extends AbstractController 
{
    public function list()
    {
        //
    }

    public function create()
    {
        //
    }

    public function view($id)
    {
        //
    }

    public function update($id)
    {
        //
    }

    public function delete($id)
    {
        //
    }
}
```