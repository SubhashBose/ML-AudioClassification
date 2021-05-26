#!/usr/bin/env python3

import pickle, sys, os

with open('VoteC_mfcc20.model','rb') as fh:
    model=pickle.load(fh)

audio_file=sys.argv[1]

import numpy as np
import librosa
import pandas as pd

import warnings
warnings.filterwarnings('ignore')

def get_features(file=None,n_mfcc=20, target_sr=22050):
    if not file:
        (sig, sr)=(np.array([0.0]), 1.0)
    else:
        if isinstance(file,tuple):
            (sig, sr)=file
            file='SignalData'
        else:
            (sig, sr) = librosa.load(file, sr=target_sr, mono=True)
        if sr!=target_sr:
            sig=librosa.resample(sig,sr,target_sr)
            sr=target_sr
    features={'file':file}
    mfcc=np.mean(librosa.feature.mfcc(sig, sr=sr,n_mfcc=n_mfcc),axis=1)
    for i in range(len(mfcc)):
        features['mfcc_'+str(i+1)] = mfcc[i]
    features['bandwidth'] = np.mean(librosa.feature.spectral_bandwidth(sig, sr=sr))
    features['rolloff'] = np.mean(librosa.feature.spectral_rolloff(sig, sr=sr))
    features['zero_cross'] = np.mean(librosa.feature.zero_crossing_rate(sig))
    features['chroma'] = np.mean(librosa.feature.chroma_stft(sig, sr=sr))
    features['centroid'] = np.mean(librosa.feature.spectral_centroid(sig, sr=sr))
    features['rms'] = np.mean(librosa.feature.rms(sig))
    return features

'''
data=pd.DataFrame(columns=model['columns'])
data=data.append(get_features(audio_file,model['n_mfcc']),ignore_index=True)

pred=model['model'].predict(data[model['columns']])

rev_map={v:k for k,v in model['genre_mapper'].items()}

pred_names=list(map(lambda x: rev_map[x], pred))

#print('The probable Genre is '+ pred_names[0])


pred_proba=model['model'].predict_proba(data[model['columns']])
label_prob=[k for k in zip(sorted(model['genre_mapper'].keys()),pred_proba[0]) if k[1]>0.01]
'''

(sig, sr) = librosa.load(audio_file, sr=22050, mono=True)

dt=min([30,len(sig)/sr])
#dt=(len(sig))/sr

intervals=[[iv, iv+int(sr*dt)]  for iv in list(range(0,len(sig)-int(sr*dt)+1,int(sr*dt/2)))]

data=pd.DataFrame(columns=model['columns'])
for iv in intervals:
    data=data.append(get_features(( sig[iv[0]:iv[1]] , sr),model['n_mfcc']),ignore_index=True)

pred_proba=model['model'].predict_proba(data[model['columns']])
median_poba=np.median(pred_proba,axis=0)
median_poba[median_poba<0.05]=0.0
median_poba=median_poba/median_poba.sum()

label_prob=[k for k in zip(sorted(model['genre_mapper'].keys()),median_poba) if k[1]>0.0]

pred_name=sorted(label_prob, key=lambda tup: tup[1], reverse=True)[0][0]


import matplotlib.pyplot as plt
pie, ax = plt.subplots(figsize=[10,6])
labels = data.keys()
plt.pie(labels=[k[0].upper() for k in label_prob], x=[k[1] for k in label_prob], autopct="%1.f%%", explode=[0.05]*len(label_prob), pctdistance=0.5)
#plt.title("Genre Probabilities", fontsize=14)

import base64
import io
my_stringIObytes = io.BytesIO()
plt.savefig(my_stringIObytes, format='png',bbox_inches='tight')
my_stringIObytes.seek(0)
my_base64_img = base64.b64encode(my_stringIObytes.read()).decode('ascii')

#print('''<img src="data:image/png;base64, {}"/>'''.format(my_base64_img))

import json
print(json.dumps({'genre':pred_name,'prob_fig':my_base64_img}))