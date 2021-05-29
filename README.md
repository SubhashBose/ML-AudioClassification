<p align="center">
<img src="https://i.imgur.com/xUvePn0.gif" width=350px><br/>
Working example of Web App<br/>
URL: <a href="https://music-classifier.bose.dev/">https://music-classifier.bose.dev/</a><br/>
  <br>
<p>

# Machine learning based classification for Music Genres

A lot of music would be hard to classify into one single genre. Jazz fusion combines jazz harmonies with rock music, rap/hip hop artists often sample music from very different genres, and different sorts of crossover music have gained popularity (e.g. reggae rock, disco pop, country rap)

Musical genres are loosely defined, but an automatic classifier would need to be based on assigning a finite, low number of labels for each input piece. So, we aim to create a music genre classifier that assigns percentages of genre influences for a given song.


# Python Notebooks

[01.Extract Features.ipynb](https://colab.research.google.com/github/SubhashBose/ML-AudioClassification/blob/main/01.Extract%20Features.ipynb) - We download and extract the data set of audio files grouped by genres. Then we read all audio files and extract various features (e.g, bandwidth, tempo, MFCCs, etc.), and store them in '.csv' files.

[02.Model Building.ipynb](https://colab.research.google.com/github/SubhashBose/ML-AudioClassification/blob/main/02.Model%20Building.ipynb) - Here we load the saved features and explore all those features first. Then we start building various classifying models and compare their performance based on various metrics we calcuate. After finalizing an optimized model, we dump the model into a file.

[03.Classify Input.ipynb](https://colab.research.google.com/github/SubhashBose/ML-AudioClassification/blob/main/03.Classify%20Input.ipynb) - Here we outline the steps for classifying an input audio. We extract the features from audio and apply the classifier model which we saved in the previous step. The classifier assigns percentage probabilities of genres. However, for the Web APP, classification is handled by the `classify_cmdline.py` script which has the same steps outlined in this notebook.

# Setting up the Web APP
The Web APP needs to be run from a PHP-enabled web server with webroot set to `www` directory. Make sure `www/exec.sh` has executable permission, otherwise set it by `chmod +x www/exec.sh`.

For development environment, you may use PHP's built-in web server serving from `www` directory, like this:

```sh
cd www
php -S localhost:8000
```

Now you have the app running at http://localhost:8000/

### Requirements
1. Python 3 is needed. 
1. Required Python libraries are Librosa and Sklearn, and the common set of Python libraries - Pandas, Numpy, Matplotlib. These dependencies can be installed by `pip install -r requirements.txt`
1. PHP - any version 5.x or above should work, but I tested on PHP 7 only. 
1. For the production environment, Apache + PHP is recommended. Note that PHP's built-in web-server is only single-threaded.
1. For serving on the web, if a domain name is used associated with this app, make sure HTTPS is enabled, otherwise the microphone will not work. This restriction is placed by web browsers as a security mesure when using WebRTC protocol for microphone input.