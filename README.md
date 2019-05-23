# Interactive Survey Tool
A simple tool for surveying user experience about selected UI characteristics (e. g., balance, symmetry, or colorfulness) by users.

- **Input:** UI screenshots + definition of possible answers
- **Output:** a survey which allows the users to rate the UI screenshots and generate the log of their ratings

<a href="https://user-images.githubusercontent.com/1479229/58249643-bb584480-7d5f-11e9-91f8-8653e6a51545.png"><img src="https://user-images.githubusercontent.com/1479229/58249643-bb584480-7d5f-11e9-91f8-8653e6a51545.png" height="300"/></a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="https://user-images.githubusercontent.com/1479229/58249642-bb584480-7d5f-11e9-9fc4-351544a3b265.png"><img src="https://user-images.githubusercontent.com/1479229/58249642-bb584480-7d5f-11e9-9fc4-351544a3b265.png" height="300"/></a>

- It allows generating multi-page forms whose every page consists of one UI screenshot and buttons for answers of reviewers. The screen also contains buttons for the fullscreen mode. There are not any other graphical elements on the screen which could skew users' perception.
- One survey can contain multiple sets of UI screenshots. Every set can be connected with different groups of users. It is useful for the surveys which set every user work with own set of dashboards.
- The tool can generate forms containing different questions and answers buttons for every set of UI screenshots. The specifications of answers are done via a configuration file in the TOML language. Currently, the button sets are:
  - 5-point scale (e. g., Likert scale rating overall impression) - see the left figure
  - two sets of buttons evaluating the vertical or horizontal aspects of the UI (e. g., balance, symmetry) - see the right figure.
- The results of users' ratings are stored in the log files for the every form and user so that further analyses can process the results automatically.

## Releases
- **rel_anonymous:** (recommended)
  - ``?variant=1&task=balance&folder=color``
  - The sets of samples are genearted according to *task*, *folder* and *variant* (subfolder) of the GET method.
  - The results are stored in the ``log`` folder.

- **rel_users:**
  - ``?login=xlogin00&task=balance&folder=color``
  - the sets of samples are genearted according to *task*, *folder* and *login* of the GET method. The *variant* (subfolder) is chosen according to the *login*
  - The results are stored in the ``log`` folder and text file is exported for the user.
  - This release was used for students who needed to submit the exported files into the students' information system in order to accomplish the homework. They used the ``login`` just to find the dataset which was assignied to them

## Project structure
- ``css/`` - styles
- ``html/`` - initial page for instructions
- ``ini/`` - configuration of possible answers for variants (in the TOML language)
- ``js/`` - slider, actions
- ``log/`` - log files storing the results
- ``variants/`` - UI screenshots datasets
  
## Usage
- copy the files into the ``www`` folder
- set the write permission to the ``log`` folder
- specify configuration (``ini/``) and UI screenshots (``variants/``) according to the requirements 
