<p align="center">
URL: <a href="https://music-classifier.bose.dev/">https://music-classifier.bose.dev/</a><br/>
<img src="https://i.imgur.com/xUvePn0.gif" width=350px><br/>
Working example of Web App<br/>
  <br>
<p>

# Machine learning based classification for Music Genres

Description goes here..

# Python Notebooks

[01.Extract Features.ipynb](https://colab.research.google.com/github/SubhashBose/ML-AudioClassification/blob/main/01.Extract%20Features.ipynb) - We download and extract the data set of audio files with assigned genre information. Then we read all audio files and extract various features (e.g, bandwidth, tempo, MFCCs, etc.), and store them in files.

[02.Model Building.ipynb](https://colab.research.google.com/github/SubhashBose/ML-AudioClassification/blob/main/02.Model%20Building.ipynb) - Here we load the saved features and explore all those features first. Then we start building various classifying models and compare their performance based on various metrics we obtained. After finalizing an optimized model, we dump the model into a file.

[03.Classify Input.ipynb](https://colab.research.google.com/github/SubhashBose/ML-AudioClassification/blob/main/03.Classify%20Input.ipynb) - Here we outline the steps for classifying an input audio. We extract the features from audio and apply the classifier model which we saved in the previous step. The classifier assigns percentage probabilities of genres. However, for Web APP, we use the `classify_cmdline.py` script which has the same steps outlined in this notebook.

# Setting up the Web APP
The Web APP needs to be run from a PHP-enabled web server with webroot set to `www` directory.

For development environment, you may use PHP's built-in web server serving from `www` directory, like this:

```sh
cd www
php -S localhost:8000
```

Now you have the app running at http://localhost:8000/

### Requirements
1. Python 3 is needed. 
1. Common set of Python libraries should suffice (including Pandas, Numpy, Matplotlib, etc.). Additional libraries needed are Librosa and sklearn.
1. PHP - any version above 5.x should work, but I tested on PHP v7 only. 
1. For the production environment Apache + PHP is recommended. Note that PHP's built-in web-server is only single-threaded.
1. For serving on the web, if a domain name is used associated with this app, make sure HTTPS is enabled, otherwise the microphone will not work. This restriction is placed by web browsers as a security mesure when using WebRTC protocol for microphone input.